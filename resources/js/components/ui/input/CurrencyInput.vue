<script>
import { useCurrencyInput } from 'vue-currency-input'

export default {
    name: 'CurrencyInput',
    props: {
        model: Number,
        options: Object
    },
    setup(props) {
        const { inputRef } = useCurrencyInput(props.options)

        return { inputRef }
    }
}
</script>
<template>
    <input
        ref="inputRef"
        type="text"
    />
</template>
