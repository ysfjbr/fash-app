<template>
<div>
   <div v-if="isloading">
        loading ...
    </div>
   <div v-else>
        <router-link class="nav-link" :to="`/show/${ show.id }`" v-for="show in results" :key="show.id">
            {{ show.name }}
        </router-link>
    </div>
</div>
</template>

<script>
export default {
    props: ["results", "isloading"]
}
</script>

<style>

</style>
