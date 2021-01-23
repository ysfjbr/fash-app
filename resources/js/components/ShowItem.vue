<template>
<div>
    <router-link class="nav-link" :to="`/show/${ showdata.id }`">
        {{ showdata.name }}
     </router-link>
</div>
</template>

<script>
export default {
    props: ['showdata', 'islistview']
}
</script>

<style>

</style>
