<?php

namespace Tests\Volley\Security;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Locks in the authentication surface of the site.
 *
 * There is no public sign-up: registration, password resetting and the user
 * profile are intentionally disabled, and accounts are created from the console
 * with `bin/console fos:user:create`. Login itself is unchanged and stays at
 * /login.
 *
 * These are router-level assertions on purpose - they need only a booted
 * kernel, no database and no symfony/browser-kit (neither is available here).
 */
class AuthRoutesTest extends KernelTestCase
{
    /**
     * URL prefixes that must stay unreachable. Nothing at all may be routed
     * under them - not the FOSUserBundle controllers, not a replacement of
     * our own.
     */
    private const DISABLED_PATH_PREFIXES = array(
        '/register',
        '/resetting',
        '/profile',
    );

    /**
     * Identifiers of the FOSUserBundle controllers that must never be routed
     * to. A `_controller` default naming any of these re-exposes the feature
     * no matter what path or route name it hides behind.
     *
     * Listed in all three notations this project's collection can carry, since
     * the form depends on how the route was declared:
     *   - `service:method`   - FOSUserBundle's own XML routing uses this;
     *   - `Class::method`    - annotation-driven routes, and Symfony's
     *                          preferred form;
     *   - `Bundle:Ctrl:act`  - the legacy notation, still accepted in 4.3.
     *
     * `fos_user.security.controller` / SecurityController is deliberately
     * absent: it is LIVE and backs /login, /login_check and /logout.
     */
    private const DISABLED_CONTROLLER_IDS = array(
        'fos_user.registration.controller',
        'fos_user.resetting.controller',
        'fos_user.profile.controller',
        'fos_user.change_password.controller',

        'FOS\UserBundle\Controller\RegistrationController',
        'FOS\UserBundle\Controller\ResettingController',
        'FOS\UserBundle\Controller\ProfileController',
        'FOS\UserBundle\Controller\ChangePasswordController',

        'FOSUserBundle:Registration',
        'FOSUserBundle:Resetting',
        'FOSUserBundle:Profile',
        'FOSUserBundle:ChangePassword',
    );

    /**
     * Every FOSUserBundle route that lets a visitor create or recover an
     * account, or manage their own profile.
     */
    private const FORBIDDEN_ROUTE_NAMES = array(
        'fos_user_registration_register',
        'fos_user_registration_check_email',
        'fos_user_registration_confirm',
        'fos_user_registration_confirmed',
        'fos_user_resetting_request',
        'fos_user_resetting_send_email',
        'fos_user_resetting_check_email',
        'fos_user_resetting_reset',
        'fos_user_profile_show',
        'fos_user_profile_edit',
        'fos_user_change_password',
    );

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->router = self::$container->get('router');
    }

    public function testSelfServiceAuthRoutesAreNotRegistered()
    {
        $collection = $this->router->getRouteCollection();

        // Sanity: prove FOSUserBundle routes are loaded at all, so the
        // assertions below are meaningful rather than vacuously true.
        $this->assertNotNull($collection->get('fos_user_security_login'));

        foreach (self::FORBIDDEN_ROUTE_NAMES as $name) {
            $this->assertNull(
                $collection->get($name),
                sprintf('Route "%s" must not be registered: public self-service auth is disabled.', $name)
            );
        }
    }

    /**
     * The check above is keyed to route *names*, which is precise but not
     * sufficient on its own. Disabling the routes did not remove
     * FOSUserBundle: its registration controller, form types, handler and
     * listeners are all still registered in the container. Re-exposing public
     * sign-up therefore takes a single route definition under any name at all,
     * e.g.
     *
     *     volley_user_signup:
     *         path: /register
     *         defaults: { _controller: 'fos_user.registration.controller:registerAction' }
     *
     * which a name-based check cannot see. So sweep the whole RouteCollection
     * by path instead: whatever it is called and whatever controller it points
     * at, nothing may answer under a disabled auth prefix.
     */
    public function testNoRouteIsExposedUnderADisabledAuthPrefix()
    {
        $offenders = array();

        foreach ($this->router->getRouteCollection() as $name => $route) {
            foreach (self::DISABLED_PATH_PREFIXES as $prefix) {
                if (0 === strpos($route->getPath(), $prefix)) {
                    $offenders[] = sprintf('%s (%s)', $name, $route->getPath());
                }
            }
        }

        $this->assertSame(
            array(),
            $offenders,
            'No route may be exposed under a disabled auth prefix, whatever it is named: '
            .'these paths are meant to 404. Found: '.implode(', ', $offenders)
        );
    }

    /**
     * The path sweep above closes the obvious re-exposure, but only for URLs
     * under the three disabled prefixes. A route at a path nobody thought to
     * reserve - `path: /join` - still reaches the registration controller,
     * because the controller, its form types and its handler are all still
     * live services in the container.
     *
     * So sweep by destination as well as by URL: whatever a route is called
     * and wherever it sits, it may not point at a disabled FOSUserBundle
     * controller. Between the two sweeps, re-enabling public sign-up cannot be
     * done without this test going red.
     */
    public function testNoRoutePointsAtADisabledFosUserController()
    {
        $collection = $this->router->getRouteCollection();

        // Sanity: the live login route really does carry a string _controller
        // naming the security controller. This proves the sweep below is
        // reading values in a form it knows how to match, rather than passing
        // vacuously - and pins the fact that fos_user.security is excluded
        // from DISABLED_CONTROLLER_IDS on purpose.
        $login = $collection->get('fos_user_security_login');
        $this->assertNotNull($login, 'Route "fos_user_security_login" is missing.');
        $this->assertStringContainsString(
            'fos_user.security.controller',
            $login->getDefault('_controller'),
            'The login route no longer points at the live security controller.'
        );

        $offenders = array();

        foreach ($collection as $name => $route) {
            $controller = $route->getDefault('_controller');

            // Not every route has a _controller, and a route declared in PHP
            // could carry a closure rather than a string.
            if (!is_string($controller)) {
                continue;
            }

            foreach (self::DISABLED_CONTROLLER_IDS as $disabled) {
                if (false !== strpos($controller, $disabled)) {
                    $offenders[] = sprintf('%s (%s -> %s)', $name, $route->getPath(), $controller);
                }
            }
        }

        $this->assertSame(
            array(),
            $offenders,
            'No route may point at a disabled FOSUserBundle controller, whatever its path '
            .'or name: the services are still in the container, so a single route revives '
            .'the feature. Found: '.implode(', ', $offenders)
        );
    }

    public function testLoginRoutesAreUnchanged()
    {
        $expected = array(
            'fos_user_security_login' => '/login',
            'fos_user_security_check' => '/login_check',
            'fos_user_security_logout' => '/logout',
        );

        foreach ($expected as $name => $path) {
            $route = $this->router->getRouteCollection()->get($name);

            $this->assertNotNull($route, sprintf('Route "%s" is missing; admins could not sign in.', $name));
            $this->assertSame($path, $route->getPath());
        }
    }

    public function testRemovedAuthUrlsNoLongerReachFosUser()
    {
        $paths = array(
            '/register/',
            '/register/check-email',
            '/resetting/request',
            '/profile/',
            '/profile/edit',
            '/profile/change-password',
        );

        foreach ($paths as $path) {
            try {
                $match = $this->router->match($path);
            } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
                // Record the pass: no route at all is the strongest outcome, and a
                // loop that only ever continues would assert nothing at all.
                $this->addToAssertionCount(1);
                continue;
            }

            // Otherwise it fell through to the blog catch-all
            // (volley_face_blog / volley_face_post), whose ParamConverter
            // raises a 404 for an unknown category slug. What must never
            // happen is landing back on a FOSUserBundle controller.
            $this->assertStringStartsNotWith(
                'fos_user',
                $match['_route'],
                sprintf('URL "%s" still resolves to a FOSUserBundle route.', $path)
            );
        }
    }
}
