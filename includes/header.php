<?php
require_once "db.php";
require_once "auth.php";
?>

<style>
.header {
    position: fixed;
    top: 0;
    left: 260px;
    right: 0;
    height: 65px;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 0 25px;

    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);

    border-bottom: 1px solid rgba(255,255,255,0.12);
    color: #fff;
    z-index: 999;
}

.header .title {
    font-size: 13px;
    letter-spacing: 2px;
    text-transform: uppercase;
}

/* MOBILE FIX */
@media (max-width:768px){
    .header { left:0; }
}
</style>

<div class="header">
    <div class="title">Job System Dashboard</div>
</div>