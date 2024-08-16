
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{url('/')}}">
                <img src="{{asset('build/assets/images/brand/logo.png')}}" class="header-brand-img main-logo"
                    alt="Sparic logo">
                <img src="{{asset('build/assets/images/brand/logo-light.png')}}" class="header-brand-img darklogo"
                    alt="Sparic logo">
                <img src="{{asset('build/assets/images/brand/icon.png')}}" class="header-brand-img icon-logo"
                    alt="Sparic logo">
                <img src="{{asset('build/assets/images/brand/icon2.png')}}" class="header-brand-img icon-logo2"
                    alt="Sparic logo">
            </a>
        </div>
        <!-- logo-->
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
                    fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">

                <li class="slide">
                    <a class="side-menu__item has-link {{request()->is('/')?'active':''}}" href="{{url('/')}}">
                        <i class="side-menu__icon ri-home-4-line"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>
                <li class="sub-category">
                    <h3>Gestion des Utilisateurs</h3>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                            class="side-menu__icon fa fa-users "></i><span
                            class="side-menu__label">{{translate('messages.Customers')}}</span><i
                            class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu mega-slide-menu">
                        <li class="panel sidetab-menu">

                            <div class="panel-body tabs-menu-body p-0 border-0">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="side9">
                                        <ul class="sidemenu-list">
                                            <li class="mega-menu">
                                                <div class="">
                                                    <ul>
                                                        <li><a href="{{route('customer.add')}}" class="slide-item">{{translate('messages.add_customer')}}</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="mega-menu">
                                                <div class="">
                                                    <ul>
                                                        <li><a href="{{route('customer.index',['type'=>'true'])}}" class="slide-item">{{translate('messages.Real_Customers')}}</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="mega-menu">
                                                <div class="">
                                                    <ul>
                                                        <li><a href="{{route('customer.index',['type'=>'fake'])}}" class="slide-item">{{translate('messages.Fake_Customers')}}</a></li>
                                                    </ul>
                                                </div>

                                            </li>
                                        </ul>

                                    </div>

                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                            class="side-menu__icon fa fa-money "></i><span
                            class="side-menu__label">Porte-monnaie</span><i
                            class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu mega-slide-menu">
                        <li class="panel sidetab-menu">

                            <div class="panel-body tabs-menu-body p-0 border-0">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="side9">
                                        <ul class="sidemenu-list">
                                            <li class="side-menu-label1"><a href="javascript:void(0)">Porte-monnaie</a></li>
                                            <li class="mega-menu">
                                                <div class="">
                                                    <ul>
                                                        <li><a href="{{url('alerts')}}" class="slide-item"> Transactions</a></li>
                                                    </ul>
                                                </div>

                                            </li>
                                        </ul>

                                    </div>

                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="sub-category">
                    <h3>Business managment</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{route('business-setting')}}"><i
                            class="side-menu__icon ri-pages-line"></i><span
                            class="side-menu__label">Business Settings</span></a>

                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{route('app-setting.index')}}"><i
                            class="side-menu__icon ri-pages-line"></i><span
                            class="side-menu__label">App Settings</span></a>

                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{route('gifts.index')}}">
                        <i class="side-menu__icon fa fa-gift" aria-hidden="true"></i>
                        <span class="side-menu__label">Gifts</span>
                    </a>
                </li>

                <li class="sub-category">
                    <h3>Préferences settings</h3>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:"><i
                        class="side-menu__icon ri-bug-line"></i><span
                            class="side-menu__label">Preferences Addons</span><i
                            class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu mega-slide-menu">
                        <li class="panel sidetab-menu">

                            <div class="panel-body tabs-menu-body p-0 border-0">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="side9">
                                        <ul class="sidemenu-list">
                                            <li class="side-menu-label1"><a href="javascript:void(0)">Preferences Addons</a></li>
                                            <li class="mega-menu">
                                                <div class="">
                                                    <ul>
                                                        <li><a href="{{route('preference-addon.index',['position'=>0])}}" class="slide-item">Add Addon Categories</a></li>
                                                        <li><a href="{{route('preference-addon.index',['position'=>1])}}" class="slide-item">Add Addon Sub-Categories</a></li>
                                                        <li><a href="{{route('preference-addon.index',['position'=>2])}}" class="slide-item">Add Addon Sub Sub-Categories</a></li>
                                                    </ul>
                                                </div>

                                            </li>
                                        </ul>

                                    </div>

                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
        </div>
    </div>
</div>
