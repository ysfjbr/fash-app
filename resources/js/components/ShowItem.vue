<template>

<tr class="col-sm-12" v-if="islistview">
    <td>
        <router-link class="nav-link" :to="`/show/${ showdata.id }`">
            <img :src="showdata.image.medium" :alt="showdata.name" width="50" height="auto">
        </router-link>
    </td>
    <td>
        <router-link class="nav-link" :to="`/show/${ showdata.id }`">
            {{ showdata.name }}
        </router-link>
     </td>
     <td>
     </td>
     <td>
     </td>
</tr>
<div class="col-sm-4" v-else>

        <router-link class="nav-link" :to="`/show/${ showdata.id }`">
        <div class="card card-custom bg-white border-white border-0">
          <div class="card-custom-img" :style="'background-image: url('+showdata.image.original+');'"></div>
          <div class="card-body" style="overflow-y: auto">

            <h4 class="card-title">{{ showdata.name }}</h4>
            <p class="card-text"> {{ summary(showdata.summary) }} </p>
          </div>

        </div>
         </router-link>

</div>

</template>

<script>

const SummeryMaxLength = 200;

export default {
    props: ['showdata', 'islistview'],

    methods:{
        summary(string){
            string = string.replace(/(<([^>]+)>)/gi, ""); // remove Tags
            if(string.length > SummeryMaxLength) return string.slice(0, SummeryMaxLength-3) + '...'
            return string
        }
    }
}
</script>

<style>

.nav-link{
    padding:0;
}

.card-custom {
  overflow: hidden;
  min-height: 650px;
  box-shadow: 0 0 5px rgba(10, 10, 10, 0.3);
  margin-bottom:25px;
}

.card-custom-img {
  height: 450px;
  min-height: 400px;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  border-color: inherit;
}

/* First border-left-width setting is a fallback */
.card-custom-img::after {
  position: absolute;
  content: '';
  top: 411px;
  left: 0;
  width: 0;
  height: 0;
  border-style: solid;
  border-top-width: 40px;
  border-right-width: 0;
  border-bottom-width: 0;
  border-left-width: 545px;
  border-left-width: calc(575px - 5vw);
  border-top-color: transparent;
  border-right-color: transparent;
  border-bottom-color: transparent;
  border-left-color: inherit;
}

</style>
