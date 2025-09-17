@props(['status'])

<span @class([
    'badge',
    'badge--grey' => $status == App\Models\Order::STATUS_CREATED_NAME,
    'badge--pink' =>
        $status == App\Models\Order::STATUS_IS_SENT_TO_BDM_NAME ||
        $status ==
            App\Models\OrderProduct::STATUS_SHIPMENT_FROM_MANUFACTURER_STARTED_NAME,
    'badge--yellow' =>
        $status == App\Models\Order::STATUS_IS_SENT_TO_CONFIRMATION_NAME ||
        $status ==
            App\Models\OrderProduct::STATUS_SHIPMENT_FROM_MANUFACTURER_FINISHED_NAME,
    'badge--green' =>
        $status == App\Models\Order::STATUS_IS_CONFIRMED_NAME ||
        $status == App\Models\OrderProduct::STATUS_ARRIVED_AT_WAREHOUSE_NAME,
    'badge--orange' =>
        $status == App\Models\Order::STATUS_IS_SENT_TO_MANUFACTURER_NAME,
    'badge--red' =>
        $status == App\Models\Order::STATUS_PRODUCTION_IS_STARTED_NAME,
    'badge--blue' =>
        $status == App\Models\OrderProduct::STATUS_PRODUCTION_IS_FINISHED_NAME,
    'badge--purple' =>
        $status ==
        App\Models\OrderProduct::STATUS_IS_READY_FOR_SHIPMENT_FROM_MANUFACTURER_NAME,
])>
    {{ $status }}
</span>
