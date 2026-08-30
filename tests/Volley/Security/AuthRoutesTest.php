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
