<template>
    <div class="row">
        <div class="col-md-6">
            <h3>Billing Address</h3>
            <slot name="billing-address"></slot>
        </div>

        <div class="col-md-6">
            <h3>Shipping Address</h3>
            <div class="checkbox" style="position: absolute; top: 16px; right: 16px;">
                <label>
                    <input type="checkbox" name="different_shipping_address" v-model="differentshipping"> Different Shipping Address
                </label>
            </div>
            <i v-show="!differentshipping">Same as billing</i>
            <div v-show="differentshipping">
                <slot name="shipping-address"></slot>
            </div>
        </div>
    </div>
</template>

<script>
import coerceBoolean from '../utils/coerceBoolean'

export default {
    props: {
        'differentshipping': {
            type: Boolean,
            default: false,
            coerce: coerceBoolean
        }
    }
}
</script>
