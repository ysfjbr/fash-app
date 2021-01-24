<template>
<div>
    <navbar />
    <transition  name="fade" mode="out-in">
        <router-view />
    </transition>
</div>

</template>

<script>
export default {
}
</script>

<style>

.fade-enter-active {
    transition: all 0.3s ease;
    transform: translateY(-20px);
    opacity: 0;
}

.fade-leave-active {
    transition: all 0.3s ease;
    transform: translateY(20px);
    opacity: 0;
}
</style>
