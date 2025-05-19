<aside
    @class([
        'leftbar',
        'leftbar--collapsed' => auth()->user()->settings[
            'collapsed_leftbar'
        ],
    ])>
    <div class="leftbar__inner thin-scrollbar">
        @include('layouts.leftbar.navs.MAD')
        @include('layouts.leftbar.navs.MGMT')
    </div>
</aside>
