<?php
defined('_EXEC') or die;
?>
<section class="login" data-image="%{$path.images}%bg-header-2.jpg">
    <div class="container rows">
        <form action="/login" method="post">
            <figure>
                <img src="%{$path.images}%profiles/user.png" alt=""/>
            </figure>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="JohnDoe@mailbox.com" id="email" class="full-width"/>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="G2RfqCGr" id="password" class="full-width"/>
            </div>
            <div class="text-right">
                <input type="submit" class="btn btn-success">
            </div>
        </form>
    </div>
</section>