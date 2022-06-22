@php
$users = \Auth::user();
$profile = asset(Storage::url('uploads/avatar/'));
$logo = asset(Storage::url('uploads/logo/'));
// $currantLang = $users->currentLanguage();
$languages = Utility::languages();
$mode_setting = \App\Models\Utility::mode_layout();

if (\Auth::user()->type == 'employee') {
    $userTask = App\Models\ProjectTask::where('assign_to', \Auth::user()->id)
        ->where('time_tracking', 1)
        ->first();
} else {
    $userTask = App\Models\ProjectTask::where('time_tracking', 1)->first();
}
$unseenCounter = App\Models\ChMessage::where('to_id', Auth::user()->id)
    ->where('seen', 0)
    ->count();
@endphp

<header
    class="dash-header  {{ isset($mode_setting['cust_theme_bg']) && $mode_setting['cust_theme_bg'] == 'on' ? 'transprent-bg' : '' }}">
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
               
                
                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <img class="theme-avtar"
                            @if (!empty($users->avatar)) src="{{ $profile . '/' . $users->avatar }}" @else  avatar="{{ $users->name }}" @endif></span>
                        <span class="hide-mob ms-2">{{ $users->name }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">

                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{ __('Profile') }}</span>
                        </a>

                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                            class="dropdown-item">
                            <i class="ti ti-power"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>

            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
                @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
                    <li class="dash-h-item {{ !empty($userTask) ? 'mt-3' : '' }}">
                        @if (empty($userTask))
                            <a class="dash-head-link me-0" href="{{ route('project.all.task.kanban') }}">
                                <i class="ti ti-clock"></i>
                                <span class="sr-only"></span>
                            </a>
                        @else
                            <a class="dash-head-link me-0" href="{{ route('project.all.task.kanban') }}">
                                <i class="ti ti-clock"></i>
                                <span class="sr-only"></span>
                            </a>
                        @endif

                        <div class="col-auto">
                            <div class="timer-counter"></div>
                        </div>
                        <div class="col-auto">
                            <p class="start-task"></p>
                        </div>

                    </li>
                @endif


                @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
                    <li class="dropdown dash-h-item drp-notification">
                        <a class="dash-head-link arrow-none me-0" href="{{ url('chats') }}" aria-haspopup="false"
                            aria-expanded="false">
                            <i class="ti ti-brand-hipchat"></i>
                            <span class="bg-danger dash-h-badge message-toggle-msg message-counter custom_messanger_counter beep"> {{ $unseenCounter }}<span
                                    class="sr-only"></span></span>
                        </a>

                    </li>
                @endif
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{ Str::upper($currantLang) }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        @foreach ($languages as $language)
                            <a href="{{ route('change.language', $language) }}"
                                class="dropdown-item @if ($language == $currantLang)  active-language text-primary   @endif">
                                <span> {{ Str::upper($language) }}</span>
                            </a>
                        @endforeach

                        @if (\Auth::user()->type == 'company')
                        <div class="dropdown-divider m-0"></div>
                            <a href="{{ route('manage.language', [$currantLang]) }}" class="dropdown-item text-primary">
                                <span> {{ __('Manage Language') }}</span>
                            </a>
                        @endif

                    </div>
                    
                </li>
            </ul>
        </div>
    </div>
</header>
