window.axios = axios;

new Vue({
  el: "#app",
  data() {
    return {
      ads: {}
    };
  },
  async mounted() {
    path = window.location.pathname;

    var self = this;
    setTimeout(function(){ 
      axios
      .get("/!/ads/ads" + path)
      .then(response => {
        self.ads = response.data;
      }); 
    
    }, 50);
    
  },
  computed: {
    topAd() {
      if (this.ads.top) {
        return this.ads.top[0];
      } else {
        return {};
      }
    },
    middleAd() {
      if (this.ads.middle) {
        return this.ads.middle[0];
      } else {
        return {};
      }
    },
    smallMiddleAds() {
      if (this.ads.small_middle) {
        return this.shuffle(this.ads.small_middle);
      } else {
        return {};
      }
    },
    newsFeedAd() {
      if (this.ads.news_feed) {
        return this.ads.news_feed[0];
      } else {
        return {};
      }
    },
    sideAds() {
      if (this.ads.side) {
        return this.shuffle(this.ads.side);
      } else {
        return {};
      }
    }
  },
  methods: {
    getRandomAd(ads) {},
    shuffle(array) {
      var currentIndex = array.length,
        temporaryValue,
        randomIndex;

      // While there remain elements to shuffle...
      while (0 !== currentIndex) {
        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
      }

      return array;
    }
  }
});
