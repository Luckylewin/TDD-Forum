<template>
    <button :class="classes" @click="subscribe" v-text="subscribe_text">订阅</button>
</template>

<script>
    export default {
        name: "subscribe-button",

        props: ['active'],

        data() {
            return {current:false}
        },

        created() {
            this.current = this.active;
        },

        computed: {
            classes() {
                return ['btn', this.current ? 'btn-primary' : 'btn-default']
            },

            subscribe_text() {
                return this.current ? '已订阅' : '订阅'
            }
        },

        methods: {
            subscribe() {
                let url = location.pathname + '/subscriptions';

                if (!this.current) {
                    axios.post(url);
                    flash('已订阅')
                } else {
                    axios.delete(url);
                    flash('取消订阅')
                }

                this.current = ! this.current;
            }
        }
    }
</script>

<style scoped>

</style>