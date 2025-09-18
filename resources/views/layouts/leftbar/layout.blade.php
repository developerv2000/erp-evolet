<aside
    @class([
        'leftbar',
        'leftbar--collapsed' => auth()->user()->settings[
            'collapsed_leftbar'
        ],
    ])>
    <div class="leftbar__inner thin-scrollbar">
        @include('layouts.leftbar.navs.MAD')
        @include('layouts.leftbar.navs.PLPD')
        @include('layouts.leftbar.navs.CMD')
        @include('layouts.leftbar.navs.PRD')
        @include('layouts.leftbar.navs.DD')
        @include('layouts.leftbar.navs.MSD')
        @include('layouts.leftbar.navs.ELD')
        @include('layouts.leftbar.navs.warehouse')
        @include('layouts.leftbar.navs.MGMT')
    </div>
</aside>
