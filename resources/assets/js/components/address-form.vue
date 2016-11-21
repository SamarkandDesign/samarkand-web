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
            <transition name="fade">
            <div v-show="differentshipping">
                <slot name="shipping-address"></slot>
            </div>
            </transition>
            <slot name="delivery-note"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'differentshipping': {
                type: Boolean,
                default: false
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