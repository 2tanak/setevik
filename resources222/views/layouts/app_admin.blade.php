<div id="app">
    <header id="header">
        <div class="project-context hidden-xs">
            <span class="label">Admin Panel</span>
            <span class="project-selector">
                {{ $user->getFullName() }}
            </span>

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
                    <li>
                        <a href="/" title="admin">
                            <i class="fa fa-lg fa-fw fa-user"></i>
                            <span class="menu-item-parent">Личный кабинет</span>
                        </a>
                    </li>
            </ul>
        </nav>
    </aside>

    <div id="main" role="main">
        <div id="content">
            @yield('content')
        </div>
    </div>

    <div class="page-footer">
        <div class="row">
            <div class="col-xs-12">
                <span class="txt-color-white">
                    @if (\Carbon\Carbon::hasTestNow())
                        <span>
                            <b>Установлено статичное время: {{ \Carbon\Carbon::now()->toDateTimeString() }}</b>
                        </span>
                    @endif
                </span>
                <span class="txt-color-white pull-right">
                    v2.0.0
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

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

<script src="{{ asset('js-any/plugin/jquery-nestable/jquery.nestable.min.js') }}"></script>

<script src="{{ asset('js-any/plugin/ckeditor/ckeditor.js') }}"></script>

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


            //badges
            function updateBadges () {
                var info = 0;
                var education = 0;

                for (var key in notifications.badges) {
                    if (notifications.badges.hasOwnProperty(key)) {
                        var n = parseInt(notifications.badges[key]);
                        if (n > 0) {
                            if (key == 'menu-partners' || key == 'menu-school-era') {
                                education += n;
                            }else{
                                info += n;
                            }
                            $('#'+key).html(notifications.badges[key]);
                        }
                    }
                }
                if (info > 0) {
                    $("#menu-parent-info").append('<em>'+info+'</em>');
                }
                if (education > 0) {
                    $("#menu-parent-education").append('<em>'+education+'</em>');
                }
            }
            //setTimeout(updateBadges, 1000);

            $('#modalInfo').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var recipient = button.data('content');
                var modal = $(this);
                modal.find('.modal-body').html(recipient);
            });

            //dynamic wallpaper
            //$('#main').css('background-image', 'url('+curBackgroundImage+')');
        };

        //reports
        $(".report-wrapper table").each(function(){
            $(this).DataTable({
                "scrollX": true,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                drawCallback: function () {
                    var api = this.api(),
                        tableId = api.table().node().id,
                        colCount = api.columns().header().length,
                        autoSumStart = 0;

                    // autosum options
                    if (tableId == '') {
                        autoSumStart = 0;
                    } else {
                        if (tableId == 'report_7') {
                            autoSumStart = 3;
                        } else {
                            autoSumStart = 4;
                        }
                    }
                    // autosum for columns
                    for (var i = autoSumStart; i < colCount; i++) {
                        $(api.column(i).footer()).html(
                            api.column(i, {page:'current'})
                                .data()
                                .reduce(function (prev, cur) {
                                    var first = parseFloat(prev.replace(/\s/g, ''));
                                    var second = parseFloat(cur.replace(/\s/g, ''));
                                    return (first + second).toLocaleString().replace(/,/g," ",);
                                })
                        );
                    }
                }
            });
        });

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