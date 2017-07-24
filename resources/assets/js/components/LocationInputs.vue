<template>

</template>

<script>
    export default {
        data() {
            return {
                locations: [],
                distanceApi: [],
                distance: this.distanceApi.data.distance,
                time: this.distanceApi.data.time
            }
        },
        mounted() {
            this.getLocations();
        },
        methods: {
            getLocations() {
                axios.get('/api/locations')
                    .then(response => {
                        this.locations  = response.data;
                    })
            },
            getDistance(from, to) {
                axios.get('/api/distance?from=' + from + '&to=' + to)
                    .then(response => {
                        this.distanceApi = response.data
                    })
            }
        }
    }
</script>