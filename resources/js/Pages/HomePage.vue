<template>
  <div class="mt-4 container">
        <div>
            <input class="form-control mr-sm-2" type="search" v-model="searchText" placeholder="Search" aria-label="Search" @keyup="submit">
        </div>

        <div v-if="isSearching">
            <search-results :isloading="isLoading" :results="searchResult" />            
        </div>
        <div v-else>        
            <div>
                <div class="btn-group m-3" role="group" aria-label="First group">
                    Results amount:
                    <div class="form-check form-check-inline ml-3" v-for="amount in amounts" :key="amount">
                        <input class="form-check-input" type="radio" name="inlineRadioItemsAmount" :id="'inlineRadio'+amount" :value="amount" v-model="showedAmount"  >
                        <label class="form-check-label" :for="'inlineRadio'+amount">{{ amount }}</label>
                    </div>
                </div>

                <div class="btn-group m-3" role="group" aria-label="First group">
                    View:
                    <div class="form-check form-check-inline ml-3">
                        <input class="form-check-input" type="radio" name="inlineRadioItemsView" id="inlineRadioGrid" :value="false" v-model="isListView"  >
                        <label class="form-check-label" for="inlineRadioGrid">Grid</label>
                    </div>

                    <div class="form-check form-check-inline ml-3">
                        <input class="form-check-input" type="radio" name="inlineRadioItemsView" id="inlineRadioList" :value="true" v-model="isListView"  >
                        <label class="form-check-label" for="inlineRadioList">List</label>
                    </div>
                </div>
            </div>

            <div ref="showsDiv">
                    <router-link class="nav-link" :to="`/show/${ show.id }`" v-for="show in showsList" :key="show.id">
                    {{ show.name }}
                    </router-link>
            </div>
            <div v-if="isLoading">
                loading ...
            </div>
        </div>
  </div>
</template>

<script>

import SearchResults from '../components/SearchResults.vue'

export default {
components: {
    "search-results": SearchResults
},

    data () {
        return {
            isLoading: false,
            isSearching: false,

            searchText:"",
            searchResult: [],

            ShowsPages:{},   // to store pages data here (for optimizing)
            allShows:[],     // Store all shows (Concat of ShowsPages)
            showsList:[],    // list of shows to display
            currPage: 1,     // Page Number from server
            currShowingPage: 0,  // showing page numbrt

            showedAmount: 25,
            amounts: [10 , 25, 50],

            isListView: false,

            polling: null
            

        }
    },
    methods: {
        submit(e){
            // when user typing 
            
            //reset polling = reset time after stop typing...
            clearInterval(this.polling)
            
            //is Loading
            this.isLoading = true

            if(this.searchText.length > 0)
            {
                /**
                when user stopped typing for 1 second start search (for Optimizng API fetching)
                 */
                this.polling = setInterval(() => {
			        this.search(this.searchText)
                    clearInterval(this.polling)
		        }, 1000)

                this.isSearching = true  // to show search results
            }

            // if search input is empty, hide search results
            else {
                this.searchResult = []
                this.isSearching = false
            }
        },
        search(search){
            this.getData({data:{search}, callback : res => {
                    this.searchResult = res.filter(show => show.score > 10).map(show => show.show)
                }
            })
        },
        getAllShow(page, callback){

            if(this.ShowsPages[page])
            {
                callback(this.ShowsPages[page])
            }
            else{
                console.log('loading');
                this.getData({data:{page}, callback : res => {
                        this.ShowsPages[page] = res
                        callback(res)
                    }
                })
            }
        },
        getData(payload)  {

            this.isLoading = true

            let url
            if(payload.objectid)
            url =`/api/${ payload.clname }/${ payload.objectid }`;
            else
            url =`/api/shows/`;
            console.log("payload",url);

            axios.get(url,{params: payload.data})
            .then(res=> payload.callback(res.data))
            .catch(err=> console.log(err))
            .finally(() => this.isLoading = false);
        },
        loadMoreShows(){

            const itemsshowing = this.showsList.length    // amount of shows that displayed
            const itemsloaded = this.allShows.length      // amount of shows that loaded to memory
            
            // check if necessery to fetch new data from the server 
            if(itemsshowing + this.showedAmount <= itemsloaded)
            {
               // if not ==> just append some shows from memory to render 
               this.showsList = this.showsList.concat(this.allShows.slice(itemsshowing,  itemsshowing + this.showedAmount))
               this.currShowingPage++
            }else{

                // if yes ==> fetch the data, append to list in memory, and append new shows to render

               this.getAllShow(this.currPage++, res => {
                   this.allShows = this.allShows.concat(res)
                   this.showsList = this.showsList.concat(this.allShows.slice(itemsshowing, itemsshowing + this.showedAmount))
                   this.currShowingPage++
               })
            }
        },
        handleScroll:function(e) {
/**
check if user scroll to end of page
 */
            if(window.innerHeight +1 > this.$refs.showsDiv.getBoundingClientRect().bottom) this.loadMoreShows();
          },
    },
    mounted () {
        this.loadMoreShows()
    },
    created:function() {
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed() {
        window.removeEventListener('scroll', this.handleScroll);
    }
}
</script>

<style>

</style>
