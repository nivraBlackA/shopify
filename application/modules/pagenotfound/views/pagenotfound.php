<?php if (!defined('BASEPATH')) exit('No direct script access allowed...');?>
<div class="container-fluid">
    <div class="row pt-5">
        <div class="col-md-4 offset-md-2">
            <h1 class="display-4 mt-5 pt-5">Nothing to see here<br/>
            </h1>
            <a class="btn btn-primary" href="<?=base_url()?>">Let's go back to Home</a>

            <h1 style="font-weight: 300" class="mt-5 pt-5">404 Page not found</h1>
        </div>
        <div class="col-md-4">
            <div id="lottie"></div>
            <script>
                var animation = bodymovin.loadAnimation({
                    container: document.getElementById('lottie'),
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: '<?=base_url()?>assets/lottie/pork-dance.json'
                });
            </script>

        </div>
    </div>
</div>