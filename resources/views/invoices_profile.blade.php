<x-app-layout :aimx="$aimx">

    <div class="container">
        @include($aimx['module']. '::'. $aimx['code']. '_profile_header', ['aimx' => $aimx, 'tab' => $tab])
        @include($aimx['module'].'::'.$aimx['code'].'_profile_overview_form', ['invoice' => $invoice])
        @include($aimx['module'].'::'.$aimx['code'].'_profile_navigation', ['invoice' => $invoice])
        @include($aimx['module']. '::'. $aimx['code']. '_profile_'.$tab , ['aimx' => $aimx, 'tab' => $tab])
    </div>

</x-app-layout>
