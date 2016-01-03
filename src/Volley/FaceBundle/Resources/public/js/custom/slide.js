 var elements = document.getElementsByName('volley_bundle_facebundle_slide[type]');
    for(var i = 0; i < elements.length; i++)
    {
        elements[i].addEventListener('change', (e) => {
            if (e.target.value == 0) {
                document.getElementById('volley_bundle_facebundle_slide_link').parentNode.style.display = 'none';
                document.getElementById('volley_bundle_facebundle_slide_post').parentNode.style.display = 'block';
            }
            if (e.target.value == 1) {
                document.getElementById('volley_bundle_facebundle_slide_post').parentNode.style.display = 'none';
                document.getElementById('volley_bundle_facebundle_slide_link').parentNode.style.display = 'block';
            }
        });
    }