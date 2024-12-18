<aside
    @class([
        'leftbar',
        'leftbar--collapsed' => auth()->user()->settings[
            'collapsed_dashboard_leftbar'
        ],
    ])>
    <div class="leftbar__inner thin-scrollbar">
        <div class="leftbar__section leftbar__section--main">
            <p class="leftbar__section-title">Основное</p>

            <nav class="leftbar__nav">

            </nav>
        </div>
    </div>
</aside>
