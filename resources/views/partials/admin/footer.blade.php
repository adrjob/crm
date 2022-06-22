<script src="{{asset('public/custom_assets/js/jquery.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/dash.js') }}"></script>
<script src="{{ asset('public/custom_assets/libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('js/letter.avatar.js')}}"></script>
@stack('pre-purpose-script-page')
{{-- FullCalendar --}}
<script src="{{ asset('assets/js/plugins/apexcharts.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>

<!-- sweet alert Js -->
<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/ac-alert.js') }}"></script>

{{-- DataTable --}}
<script src="{{ asset('assets/js/plugins/simple-datatables.js')}}"></script>
<script>
    const dataTable = new simpleDatatables.DataTable("#pc-dt-simple");
</script>

{{-- Multi Select --}}
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

<!-- date -->
<script src="{{asset('assets/js/plugins/datepicker-full.min.js')}}"></script>

<!--Botstrap switch-->
<script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>

<script src="{{ asset('js/chatify/autosize.js') }}"></script>
<script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>

<script src="{{asset('assets/js/plugins/choices.min.js')}}"></script>



<script>
    if ($(".multi-select").length > 0) {
              $( $(".multi-select") ).each(function( index,element ) {
                  var id = $(element).attr('id');
                     var multipleCancelButton = new Choices(
                          '#'+id, {
                              removeItemButton: true,
                          }
                      );
              });
         }
  </script>
<!-- report data table-->
<script>
    const table = new simpleDatatables.DataTable(".pc-dt-export");
    document.querySelector("button.csv").addEventListener("click", () => {
        table.export({
            type: "csv",
            download: true,
            lineDelimiter: "\n\n",
            columnDelimiter: ";"
        })
    })
    document.querySelector("button.xlsx").addEventListener("click", () => {
        table.export({
            type: "xlsx",
            download: true,
            tableName: "export_table"
        })
    })
    document.querySelector("button.pdf").addEventListener("click", () => {
        table.export({
            type: "pdf",
            download: true,
        })
    })
</script>

<script>
function taskCheckbox() {
  var checked = 0;
  var count = 0;
  var percentage = 0;

  count = $("#check-list input[type=checkbox]").length;
  checked = $("#check-list input[type=checkbox]:checked").length;
  percentage = parseInt(((checked / count) * 100), 10);
  if (isNaN(percentage)) {
      percentage = 0;
  }
  $(".custom-label").text(percentage + "%");
  $('#taskProgress').css('width', percentage + '%');


  $('#taskProgress').removeClass('bg-warning');
  $('#taskProgress').removeClass('bg-primary');
  $('#taskProgress').removeClass('bg-success');
  $('#taskProgress').removeClass('bg-danger');

  if (percentage <= 15) {
      $('#taskProgress').addClass('bg-danger');
  } else if (percentage > 15 && percentage <= 33) {
      $('#taskProgress').addClass('bg-warning');
  } else if (percentage > 33 && percentage <= 70) {
      $('#taskProgress').addClass('bg-primary');
  } else {
      $('#taskProgress').addClass('bg-success');
  }
}
</script>



<script>
  feather.replace();
  var pctoggle = document.querySelector("#pct-toggler");
  if (pctoggle) {
    pctoggle.addEventListener("click", function () {
      if (
        !document.querySelector(".pct-customizer").classList.contains("active")
      ) {
        document.querySelector(".pct-customizer").classList.add("active");
      } else {
        document.querySelector(".pct-customizer").classList.remove("active");
      }
    });
  }




 
</script>

@if(Utility::getValByName('gdpr_cookie') == 'on')
    <script type="text/javascript">

        var defaults = {
            'messageLocales': {
                /*'en': 'We use cookies to make sure you can have the best experience on our website. If you continue to use this site we assume that you will be happy with it.'*/
                'en': "{{Utility::getValByName('cookie_text')}}"
            },
            'buttonLocales': {
                'en': 'Ok'
            },
            'cookieNoticePosition': 'bottom',
            'learnMoreLinkEnabled': false,
            'learnMoreLinkHref': '/cookie-banner-information.html',
            'learnMoreLinkText': {
                'it': 'Saperne di più',
                'en': 'Learn more',
                'de': 'Mehr erfahren',
                'fr': 'En savoir plus'
            },
            'buttonLocales': {
                'en': 'Ok'
            },
            'expiresIn': 30,
            'buttonBgColor': '#d35400',
            'buttonTextColor': '#fff',
            'noticeBgColor': '#000000',
            'noticeTextColor': '#fff',
            'linkColor': '#009fdd'
        };
    </script>
    <script src="{{ asset('js/cookie.notice.js')}}"></script>
@endif




<script>
    var timer = '';
    var timzone = '{{env("TIMEZONE")}}';

    function TrackerTimer(start_time) {
        timer = setInterval(function () {
            var start = new Date(start_time);
            //var end = new Date();

            var here = new Date();
            var end = changeTimezone(here, timzone);

            var hrs = end.getHours() - start.getHours();

            var min = end.getMinutes() - start.getMinutes();
            var sec = end.getSeconds() - start.getSeconds();
            var hour_carry = 0;
            var Timer = $(".timer-counter");
            var minutes_carry = 0;
            if (min < 0) {
                min += 60;
                hour_carry += 1;
            }
            hrs = hrs - hour_carry;
            if (sec < 0) {
                sec += 60;
                minutes_carry += 1;
            }
            min = min - minutes_carry;

            Timer.text(minTwoDigits(hrs) + ':' + minTwoDigits(min) + ':' + minTwoDigits(sec));
        }, 1000);
    }


    function minTwoDigits(n) {
        return (n < 10 ? '0' : '') + n;
    }

    function changeTimezone(date, ianatz) {

        var invdate = new Date(date.toLocaleString('en-US', {
            timeZone: ianatz
        }));
        var diff = date.getTime() - invdate.getTime();
        return new Date(date.getTime() - diff);

    }

    function toastrs(title, message, type) {
    var o, i;
    var icon = '';
    var cls = '';
    if (type == 'success') {
        icon = 'fas fa-check-circle';
        // cls = 'success';
        cls = 'primary';
    } else {
        icon = 'fas fa-times-circle';
        cls = 'danger';
    }

    console.log(type,cls);
    $.notify({ icon: icon, title: " " + title, message: message, url: "" }, {
        element: "body",
        type: cls,
        allow_dismiss: !0,
        placement: {
            from: 'top',
            align: 'right'
        },
        offset: { x: 15, y: 15 },
        spacing: 10,
        z_index: 1080,
        delay: 2500,
        timer: 2000,
        url_target: "_blank",
        mouse_over: !1,
        animate: { enter: o, exit: i },
        // danger
        template: '<div class="toast text-white bg-'+cls+' fade show" role="alert" aria-live="assertive" aria-atomic="true">'
                +'<div class="d-flex">'
                    +'<div class="toast-body"> '+message+' </div>'
                    +'<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'
                +'</div>'
            +'</div>'
        // template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
    });
}
</script>


{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script> --}}
<script type="text/javascript">
    
$(function(){
    $(document).on("click",".show_confirm",function(){
        var form = $(this).closest("form");
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This action can not be undone. Do you want to continue?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    });
});
</script>


@php
    if(Auth::check())
    {
        if(\Auth::user()->type == 'employee')
        {
        $userTask = App\Models\ProjectTask::where('assign_to', \Auth::user()->id)->where('time_tracking', 1)->first();
        }
        else
        {
            $userTask = App\Models\ProjectTask::where('time_tracking', 1)->first();
        }
    }

@endphp

@if(!empty($userTask))
    @php

        $lastTime = App\Models\ProjectTaskTimer::where('task_id', $userTask->id)->orderBy('id','desc')->first();
    @endphp
    <script>
        TrackerTimer("{{$lastTime->start_time}}");
        $('.start-task').html("{{$userTask->title}}");

    </script>

@endif

@stack('script-page')
