<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <!-- <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <span class="brand-text font-weight-bold">Food Delivery App</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
<!--            <div class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>-->
            <div class="info">
                <a href="#" class="d-block"><?= Yii::$app->user->isGuest ? "" :Yii::$app->user->identity->email?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
//                    [
//                        'label' => 'Starter Pages',
//                        'icon' => 'tachometer-alt',
//                        'badge' => '<span class="right badge badge-info">2</span>',
//                        'items' => [
//                            ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
//                            ['label' => 'Inactive Page', 'iconStyle' => 'far'],
//                        ]
//                    ],
//                    ['label' => 'Administrare Categorii', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
                    ['label' => 'Restaurante', 'header' => true],
                    ['label' => 'Restaurante', 'url' => ['restaurante/index'], 'icon' => 'store'],
                    ['label' => 'Administrare', 'header' => true],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Comenzi', 'url' => ['comenzi/index'],'icon' => 'star'],
                    ['label' => 'Categorii' ,'url' => ['categorii/index'] , 'name' => 'categorii', 'icon' => 'list'],
                    ['label' => 'Produse', 'url' => ['produse/index'], 'icon' => 'utensils'],
                    ['label' => 'Stocuri', 'url' => ['stocuri/index'], 'icon' => 'boxes'],
                    ['label' => 'Persoane', 'url' => ['persoane/index'], 'icon' => 'users'],
                    ['label' => 'Functii', 'url' => ['functii/index'],'icon' => 'chart-bar'],
                    ['label' => 'Setari Livrare', 'url' => ['setari-livrare/index'], 'icon' => 'cog'],
//                    ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
//                    ['label' => 'Level1'],
//                    [
//                        'label' => 'Level1',
//                        'items' => [
//                            ['label' => 'Level2', 'iconStyle' => 'far'],
//                            [
//                                'label' => 'Level2',
//                                'iconStyle' => 'far',
//                                'items' => [
//                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
//                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
//                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
//                                ]
//                            ],
//                            ['label' => 'Level2', 'iconStyle' => 'far']
//                        ]
//                    ],
//                    ['label' => 'Level1'],
//                    ['label' => 'LABELS', 'header' => true],
//                    ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
//                    ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
//                    ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>