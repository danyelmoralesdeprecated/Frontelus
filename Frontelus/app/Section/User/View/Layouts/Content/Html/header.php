<?php
    defined('_EXEC') or die;
?>
<header id="topbar" class="scroll-hide">
    <div class="container" data-position="relative">
        <div class="barspace">
            <figure class="logo" data-goto="/flags">
                <img src="%{$path.images}%trackflag-logo.png" alt="logotype"/>
            </figure>
            <nav class="menu">
                <ul>
                    <li><a href="/myFlags">My Flags</a></li>
                    <li><a href="/myRecomendations">My Recomendations</a></li>
                </ul>
            </nav>
            <form action="/search" class="topsearch" autocomplete="off">
                <input name="s" type="search" placeholder=""/>
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
            <div class="notifications">
                <p><i class="fa fa-bell"></i><span>1</span></p>
                <div class="dropdown">
                    <p class="up">Notificaciones</p>
                    <div>
                        <a href="">Notificacion 1</a>
                        <a href="">Notificacion 2</a>
                        <a href="">Notificacion 3</a>
                    </div>
                    <p class="down"><a href="#">Ver todo</a></p>
                </div>
            </div>
        </div>
        <div class="img_profile" data-goto="/user">
            <img src="%{$path.images}%profiles/%{Content.Text.info.imgProfile}%" alt=""/>
        </div>
    </div>
</header>