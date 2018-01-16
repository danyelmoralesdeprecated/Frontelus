<?php
defined('_EXEC') or die;
?>
<section class="gray parallax" data-image="%{$path.images}%parallax.jpg">
    <div class="profile overlay"></div>
    <div class="container">
        <div class="display-table">
            <figure class="my-profile">
                <img src="%{$path.images}%profiles/%{Content.Text.info.imgProfile}%" alt=""/>
            </figure>
            <div class="my-info-profile">
                <h2>%{Content.Text.info.fullname}%</h2>
                <h4>%{Content.Text.info.username}%</h4>
                <div class="social-buttons">
                    <a href="#" class="btn btn-primary">Seguir</a>
                    %{Content.Html.info.socialNetwork}%
                </div>
                <div class="info-user">
                    <a><span>%{Content.Text.numbers.recommendations}%</span>recommended</a>
                    <a><span>%{Content.Text.numbers.followers}%</span>followers</a>
                    <a><span>%{Content.Text.numbers.following}%</span>following</a>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="main">
    <div class="container">
        <div class="nav-profile">

        </div>
    </div>
</section>
<div style="height: 1000px;"></div>