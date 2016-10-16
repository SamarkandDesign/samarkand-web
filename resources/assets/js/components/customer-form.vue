<template>
    <div>
        <slot name="emailsection"></slot>

        <div class="checkbox">
            <label>
                <input type="checkbox" name="create_account" v-model="createNewAccount"> Create an account?
            </label>
        </div>

        <div v-show="createNewAccount">
            <slot name="passwordsection"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        createNewAccount: {
            type: Boolean,
            default: false
        }
    }
}
</script>
