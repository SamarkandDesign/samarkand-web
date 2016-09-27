<template>
    <div class="row">
        <div class="col-md-6">
            <h3>Billing Address</h3>
            <slot name="billing-address"></slot>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <h3>Shipping Address</h3>

                </div>
                <div class="col-md-6">
                    <div class="checkbox different-shipping-checkbox">
                        <label>
                            <input type="checkbox" name="different_shipping_address" v-model="differentshipping"> Use a different address
                        </label>
                    </div>
                </div>
            </div>
            <p v-show="!differentshipping">Same as billing</p>
            <div v-show="differentshipping" transition="fade">
                <slot name="shipping-address"></slot>
            </div>
            <slot name="delivery-note"></slot>
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

<style>
    .different-shipping-checkbox {
        margin-top: 26px;
        margin-bottom: 0;
    }
</style>