<template>
    <button :class="classes" @click="subscribe">订阅</button>
</template>

<script>
    export default {
        name: "subscribe-button",

        props: ['active'],

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-default']
            }
        },

        methods: {
            subscribe() {
                let url = location.pathname + '/subscriptions';

                if (this.active) {
                    axios.post(url);
                    flash('已订阅')
                } else {
                    axios.delete(url);
                    flash('取消订阅')
                }


                this.active = ! this.active;
            }
        }
    }
</script>

<style scoped>

</style>