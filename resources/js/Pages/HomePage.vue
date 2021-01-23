<template>
  <div class="mt-4 container">
        <div>
            <input class="form-control mr-sm-2" type="search" v-model="searchText" placeholder="Search" aria-label="Search" @keyup="submit">
        </div>

        <div v-if="error">
            <div class="alert alert-danger text-center mt-4"  role="alert">
                {{error}}
            </div>
        </div>

        <div v-if="isSearching && !error">
            <search-results :isloading="isLoading" :results="searchResult" :islistview="isListView"/>
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
                <show-items :showslist="showsList" :islistview="isListView" />

                <div class="text-center" v-if="isLoading">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
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

            showedAmount: 15,
            amounts: [15 , 30, 60],

            isListView: false,

            polling: null,
            error: ""
        }
    },
    methods: {
        submit(e){
            // when user typing

            //reset polling = reset time after stop typing...
            clearInterval(this.polling)
            this.searchResult = []
            //is Loading
            this.isLoading = true

            // clear Error
            this.error = "";

            if(this.searchText.length > 0)
            {
                /**
                when user stopped typing for 0.5 second start search (for Optimizng API fetching)
                 */
                this.polling = setInterval(() => {
			        this.search(this.searchText)
                    clearInterval(this.polling)
		        }, 500)

                this.isSearching = true  // to show search results
            }

            // if search input is empty, hide search results
            else {
                this.isSearching = false
            }
        },
        search(search){
            this.getData({data:{search}, callback : res => {
                    this.searchResult = res && res.filter(show => show.score > 10).map(show => show.show)
                }
            })
        },
        getAllShow(page, callback){

            if(this.ShowsPages[page])
            {
                callback(this.ShowsPages[page])
            }
            else{
                //console.log('loading');
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
            //console.log("payload",url);

            axios.get(url,{params: payload.data})
            .then(result=> payload.callback(result.data))
            .catch(error=> this.error = error)
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

            if(this.$refs.showsDiv && window.innerHeight +1 > this.$refs.showsDiv.getBoundingClientRect().bottom) this.loadMoreShows();
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
    },
    watch: {
        showedAmount: function (va) {
            this.showsList = []
            this.loadMoreShows()
        }
    }
}
</script>

<style>

</style>
