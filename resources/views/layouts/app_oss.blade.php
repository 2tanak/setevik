<div id="app">
    <header id="header">
        <div class="project-context hidden-xs" style="visibility: hidden">
            <span class="label">Projects:</span>
            <span class="project-selector dropdown-toggle" data-toggle="dropdown">Recent projects <i class="fa fa-angle-down"></i></span>

            <!-- Suggestion: populate this list with fetch and push technique -->
            <ul class="dropdown-menu">
                <li>
                    <a href="javascript:void(0);">Online e-merchant management system - attaching integration with the iOS</a>
                </li>
                <li>
                    <a href="javascript:void(0);">Notes on pipeline upgradee</a>
                </li>
                <li>
                    <a href="javascript:void(0);">Assesment Report for merchant account</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="javascript:void(0);"><i class="fa fa-power-off"></i> Clear</a>
                </li>
            </ul>
        </div>

        <div class="pull-left">
            <div id="hide-menu" class="btn-header pull-left">
                <span>
                    <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu">
                        <i class="fa fa-reorder"></i>
                    </a>
                </span>
            </div>

        </div>

        <!-- pulled right: nav area -->
        <div class="pull-right">
            <!-- #MOBILE -->
            <!-- Top menu profile link : this shows only when top menu is active -->
            <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
                <li class="">
                    <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown">
                        <img src="{{ $user->photo }}" alt="Me" class="online" />
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="login.html" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
                        </li>
                    </ul>
                </li>
            </ul>

            
			<div id="logout" class="btn-header transparent pull-right">
                <span>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="get" style="display: none;">
                        
                    </form>
					
                </span>
            </div>
			
			
			
			
        </div>
    </header>

    <aside id="left-panel">
		@if(File::exists('./'.$user->photo))

        <oss-login-info
                :photo="'{{ $user->photo }}'"
                :public_id="'{{ $user->getPublicId() }}'"
                :full_name="'{{ $user->getFullName() }}'"
                :has_activity_oss="'{{ $user->has_activity_oss }}'">
        </oss-login-info>
		
		@else
		
		<oss-login-info
                :photo="'null'"
                :public_id="'{{ $user->getPublicId() }}'"
                :full_name="'{{ $user->getFullName() }}'"
                :has_activity_oss="'{{ $user->has_activity_oss }}'">
        </oss-login-info>
		
        @endif
        <nav>
            <ul>
                @foreach ($menus as $menu)
                    <li class="{{ $menu->is_active ? 'active' : ''}}">
                        @if (count($menu['children']) > 0)
                            <a href="#" title="{{ $menu->name }}">
                                <i class="{{ $menu->icon }} {{ $menu->is_active ? 'text-primary' : ''}}">
                                    @if ($menu['badges_count'] && $menu['badges_count'] > 0)
                                        <em>{{ $menu['badges_count'] }}</em>
                                    @endif
                                </i>
                                <span class="menu-item-parent">{{ $menu->name }}</span>
                            </a>
                            <ul>
                                @foreach ($menu['children'] as $child)
                                    <li class="{{ isset($child['is_active']) ? 'active' : ''}}">
                                        <a href="{{ $child['link'] }}" title="{{ $child['name'] }}" class="{{ isset($child['is_active']) ? 'text-primary' : ''}}">
                                            <i class="{{ $child['icon'] }}"></i>
                                            <span class="menu-item-parent">{{ $child['name'] }}</span>
                                            @if ($child['badges_count'] > 0)
                                                <span class="badge bg-color-red pull-right inbox-badge">
                                            {{ $child['badges_count'] }}
                                        </span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ $menu->link }}" title="{{ $menu->name }}" class="{{ $menu->is_active ? 'text-primary' : ''}}">
                                <i class="{{ $menu->icon }} {{ $menu->is_active ? 'text-primary' : ''}}">
                                    @if ($menu['badges_count'] && $menu['badges_count'] > 0)
                                        <em>{{ $menu['badges_count'] }}</em>
                                    @endif
                                </i>
                                <span class="menu-item-parent">{{ $menu->name }}</span>
                            </a>
                        @endif
                    </li>
                @endforeach

                @if ($user->is_active)
                    <li>
                        <br>
                        <div class="text-center">
                            <a href="/oss/be-partner-request" class="btn btn-danger btn-be-partner pull-center">
                                <span class="txt-color-white">Стать Партнёром SIB<br>и поступить в Школу</span>
                            </a>
                        </div>
                    </li>
                @endif
            </ul>
        </nav>
    </aside>

    <div id="main" role="main">
        <div id="ribbon">
            @if ($user->is_active)
                <div class="row">
                    <div class="col-xs-12 col-sm-12 padding-top-10" id="pb-subscription-wrapper">
                        <p>
                            <span class="hidden-xs upgrade-period">Абонемент - осталось:</span>
                            <span class="visible-xs-inline">Абонемент:</span>
                            <span class="txt-color-blue pull-right">
                                <span id="pb-subscription-countdown"></span>
                            </span>
                        </p>
						@php
						//dd($subscription);
						
						@endphp
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-color-blue"
                                 id="pb-subscription"
                                 data-day-start="{{ \Carbon\Carbon::now()->format('d.m.Y H:i:s') }}"
                                 data-day-end="{{ $subscription->expired_at->format('d.m.Y H:i:s') }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div id="content">
            @yield('content')
        </div>
    </div>

    <div class="page-footer">
        <div class="row">
            <div class="col-xs-12">
                <span class="txt-color-white">© Online Smart System<span class="hidden-xs">, {{ date('Y') }}</span></span>
                <span class="txt-color-white pull-right">
                    <b>Обратная связь<span class="hidden-xs"> (Telegram)</span>:</b> <a href="https://t.me/era_school_02" style="color:#fff; text-decoration: underline">@era_school_02</a>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->



<script src="{{ asset('js/app.js') }}?v=3.1"></script>




<script src="{{ asset('https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}"></script>
<script>
    if (!window.jQuery) {
        document.write('<script src="js-any/libs/jquery-3.2.1.min.js"><\/script>');
    }
</script>
<script src="{{ asset('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js') }}"></script>
<script>
    if (!window.jQuery.ui) {
        document.write('<script src="js-any/libs/jquery-ui.min.js"><\/script>');
    }
</script>
<script src="{{ asset('js-any/app.config.js') }}"></script>
<script src="{{ asset('js-any/plugin/jquery-touch/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('js-any/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('js-any/notification/SmartNotification.min.js') }}"></script>
<script src="{{ asset('js-any/smartwidgets/jarvis.widget.min.js') }}"></script>
<script src="{{ asset('js-any/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js') }}"></script>
<script src="{{ asset('js-any/plugin/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('js-any/plugin/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js-any/plugin/masked-input/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('js-any/plugin/select2/select2.min.js') }}"></script>
<script src="{{ asset('js-any/plugin/bootstrap-slider/bootstrap-slider.min.js') }}"></script>
<script src="{{ asset('js-any/plugin/msie-fix/jquery.mb.browser.min.js') }}"></script>
<script src="{{ asset('js-any/plugin/fastclick/fastclick.min.js') }}"></script>

<!--[if IE 8]>
<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
<![endif]-->

<script src="{{ asset('js-any/app.js') }}"></script>
<script src="{{ asset('js-any/plugin/jqgrid/jquery.jqGrid.min.js') }}"></script>
<script src="{{ asset('js-any/plugin/jqgrid/grid.locale-en.min.js') }}"></script>
<script src="{{ asset('https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js') }}"></script>
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js') }}"></script>

<script src="{{ asset('js-any/plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
<script src="{{ asset('js-any/plugin/fuelux/wizard/wizard.min.js') }}"></script>

<script src="{{ asset('js-any/plugin/superbox/superbox.min.js') }}"></script>


<script src="js/scrop/jquery.Jcrop.min.js?v=54.4"></script>
    

<script src="js/scrop/crop.js?v=54.4"></script>

@yield('extra-script')

<script>
    $(document).ready(function() {
        pageSetUp();

        var pagefunction = function() {
            moment.locale('ru');

            /**
             * Plurals
             */
            function plural_str(a, str1, str2, str3) {
                if ( a % 10 === 1 && a % 100 != 11 ) return str1;
                else if ( a % 10 >= 2 && a % 10 <= 4 && (a % 100 < 10 || a % 100 >= 20)) return str2;
                else return str3;
            }

            /**
             * Subscriptions
             */
            var pbSubscription = $('#pb-subscription');
            if (pbSubscription) {
                var pbSub = pbSubscription.progressbar();
                var pbDayStart = moment(pbSub.attr('data-day-start'), 'DD.MM.YYYY hh:mm:ss').format('x');
                var pbDayEnd = moment(pbSub.attr('data-day-end'), 'DD.MM.YYYY hh:mm:ss').format('x');
				/*------------------------------------*/

                var x = new Date();
                var offset= x.getTimezoneOffset();
                var hours1= x.getHours();
				var seconds1= x.getSeconds();
			    mockva = -180;
                x.setMinutes(x.getMinutes() + mockva);
                var hours2= x.getHours();
				var seconds2= x.getSeconds();
                if(hours1 > hours2){
	               var hours = hours1 - hours2;
				   //var seconds = seconds1 - seconds2;
                   pbDayEnd = moment(pbDayEnd,'x').add(hours,'H').format('x');
				  //pbDayEnd = moment(pbDayEnd,'x').add(seconds,'seconds').format('x');

               }else{
	              var hours = hours2 - hours1;
                  pbDayEnd = moment(pbDayEnd,'x').subtract('H', hours).format('x');
               }
					/*------------------------------------*/

                if (pbDayEnd < moment().format('x')) {
                    $('.upgrade-period').text('Срок абонемента истёк');
					
                    pbSub.addClass('bg-color-red');
                } else {
                    $('#pb-subscription-countdown')
                        .countdown(pbDayEnd, function(event) {
                            var d = event.strftime('%-D ');
                            $(this).text(d + plural_str(Number(d), 'день', 'дня', 'дней') + event.strftime(' %-H:%-M:%-S'));
                        })
						
                        .on('update.countdown', function() {
                            pbSub.attr('data-transitiongoal', 100 - ((moment().format('x') - pbDayStart) * 100 / (pbDayEnd-pbDayStart))).progressbar();
                        })
                        .on('finish.countdown', function(){
                            $('.upgrade-period').text('Срок абонемента истёк');
                            pbSub.addClass('bg-color-red');
                        });
                }
            }




            $('#modalInfo').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var recipient = button.data('content');
                var modal = $(this);
                modal.find('.modal-body').html(recipient);
            });

            //dynamic wallpaper
            //$('#main').css('background-image', 'url('+curBackgroundImage+')');
        };

        $(".block-import table").DataTable({
            "scrollX": true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            drawCallback: function () {
                var api = this.api(),
                    tableId = api.table().node().id,
                    colCount = api.columns().header().length,
                    autoSumStart = 0;
            }
        });

        //mobile search input focus
        $("#search-mobile").on('click', function() {
            var ti = $("#search-fld");
            var fi = $("<input type='text' />")
                .css({
                    position: "absolute",
                    width: ti.outerWidth(), // zoom properly (iOS)
                    height: 0, // hide cursor (font-size: 0 will zoom to quarks level) (iOS)
                    opacity: 0, // make input transparent :]
                });

            // fi.prependTo($("#search-fld").parent()).focus();
            fi.prependTo($("#header")).focus();

            setTimeout(function() {
                ti.focus();
                fi.remove();
            }, 1000);
        });

        // PAGE RELATED SCRIPTS
        $('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
        $('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(':visible')) {
                children.hide('fast');
                $(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
            } else {
                children.show('fast');
                $(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
            }
            e.stopPropagation();
        });

        new ClipboardJS('.btn-copy', {
            target: function(trigger) {
                return trigger.parentNode.previousElementSibling.childNodes[0];
            }
        });

        loadScript("/js-any/plugin/bootstrap-progressbar/bootstrap-progressbar.min.js", function(){
            loadScript("/js-any/plugin/moment/moment.min.js", function(){
                loadScript("/js-any/plugin/moment/ru.js", function(){
                    loadScript("/js-any/plugin/jquery.countdown/jquery.countdown.min.js", pagefunction);
                });
            });
        });
    });

    $(window).on('resize.jqGrid', function() {
        $("#jqgrid").jqGrid('setGridWidth', $("#content").width());
    });
</script>