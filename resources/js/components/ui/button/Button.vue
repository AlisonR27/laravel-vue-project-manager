<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Primitive, type PrimitiveProps } from 'reka-ui'
import { type ButtonVariants, buttonVariants } from '.'

interface Props extends PrimitiveProps {
    variant?: ButtonVariants['variant']
    size?: ButtonVariants['size']
    class?: HTMLAttributes['class'],
    name?: string
}

const props = withDefaults(defineProps<Props>(), {
  as: 'button',
})
</script>

<template>
  <Primitive
    data-slot="button"
    class="not-disabled:cursor-pointer disabled:cursor-not-allowed"
    :as="as"
    :as-child="asChild"
    :class="cn(buttonVariants({ variant, size }), props.class)"
    :name="name"
  >
    <slot />
  </Primitive>
</template>
