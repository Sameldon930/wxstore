{{--
    日期搜索组件
    合适的文档中 @include('compoment.search_date')
    JS开始位置  @yield("date_search") (script标签外)
--}}
<div class="col-xs-3 col-lg-2">
    <div class="form-group">
        <label for="start-at">起始日期：</label>
        <div class="input-group date">
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
            <input class="form-control" name="start_at" id="start-at" value="{{ $start_at or '' }}">
        </div>
    </div>
</div>

<div class="col-xs-3 col-lg-2">
    <div class="form-group">
        <label for="end-at">结束日期：</label>
        <div class="input-group date">
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
            <input class="form-control" name="end_at" id="end-at" value="{{ $end_at or '' }}">
        </div>
    </div>
</div>

<div class="col-xs-3 col-lg-2">
    <div class="form-group">
        <label for="range">快捷区间：</label>
        <select class="form-control" name="range" id="quick-search"
                v-on:focus="clearValue" v-on:change="changeData" v-model="range">
            <option v-for="(option,key) in options" v-cloak v-bind:value="key">
                @{{ option.text }}
            </option>
        </select>
    </div>
</div>


@section('date_search')
    <script src="/js/moment.min.js"></script>
    <script>
        $(document).on("click", ".datepicker-days .today", function () {
            $date = $(window.currentDatePicker);
            if ($date.length) {
                $date.datepicker("setDate", new Date());
                $date.datepicker("hide").blur();
                $date.find("input").blur();
            }
        });

        var quickSearch = new Vue({
            el: '#quick-search',
            created: function () {
                var _this = this;
                $('.date').datepicker({
                    autoclose: true,
                    clearBtn: true,
                    todayBtn: true,
                }).on("show", function (ev) {
                    currentDatePicker = ev.target;
                }).on("changeDate", function (ev){
                    _this.range = "";
                });

                this.options = {
                    'last_seven_days': {"text": '近七天', "startAt": this.AWeekAgo, "endAt": this.yesterday},
                    'this_week': {"text": '本周', "startAt": this.thisMonday, "endAt": this.today},
                    'last_week': {"text": '上周', "startAt": this.lastMonday, "endAt": this.lastSunday},
                    'this_month': {"text": '本月', "startAt": this.firstDayOfThisMonth, "endAt": this.today},
                    'last_month': {"text": '上月', "startAt": this.firstDayOfLastMonth, "endAt": this.lastDayOfLastMonth},
                    'last_three_month': {"text": '近三月', "startAt": this.threeMonthAgo, "endAt": this.yesterday},
                };

                $.each(this.options, function (key, option) {
                    if (option.startAt === _this.startAt && option.endAt === _this.endAt) {
                        _this.range = key;
                    }
                });
            },
            data: {
                range: getQueryString("range"),
                startAt: getQueryString("start_at"),
                endAt: getQueryString("end_at"),
                options: "",

                today: moment().format("YYYY-MM-DD"), // 今天
                yesterday: moment().subtract(1, "days").format("YYYY-MM-DD"), // 昨天
                AWeekAgo: moment().subtract(1, "week").format("YYYY-MM-DD"),   // 六天前 包括今天为近一周
                thisMonday: moment().weekday(1).format("YYYY-MM-DD"),           // 这周一
                lastMonday: moment().weekday(-6).format("YYYY-MM-DD"),          // 上周一
                lastSunday: moment().weekday(0).format("YYYY-MM-DD"),           // 上周日
                firstDayOfThisMonth: moment().date(1).format("YYYY-MM-DD"),     // 本月第一天
                firstDayOfLastMonth: moment().date(1).subtract(1, "months").format("YYYY-MM-DD"),//本月最后一天
                lastDayOfLastMonth: moment().date(0).format("YYYY-MM-DD"),      // 上个月最后一天
                threeMonthAgo: moment().subtract(3, "months").format("YYYY-MM-DD"),    //三月前第一天
            },
            methods: {
                changeData: function (e) {
                    e.target.blur(); // 失去焦点 解决select重复点击同一个option不会触发onchange事件的问题
                    var option = e.target.value;
                    this.startAt = this.options[option].startAt;
                    this.endAt = this.options[option].endAt;
                    $("#start-at").val(this.startAt);
                    $("#end-at").val(this.endAt);
                },
                clearValue: function (e) {
                    e.target.selectedIndex = -1; // 点击时改变值 解决select重复点击同一个option不会触发onchange事件的问题
                }
            }
        })
    </script>
@endsection
