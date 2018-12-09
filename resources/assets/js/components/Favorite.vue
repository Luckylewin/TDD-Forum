<template>
    <button type="submit" class="btn btn-default no-line" @click="toggle">
        <span :style="style">❤</span>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        name: "favorite",
        props: ['reply'],

        data() {
            return {
                count: this.reply.favoritesCount,
                active:this.reply.isFavorited
            }
        },

        computed: {
            style() {
                return this.active ? 'color:red' : '';
            },

            endpoint() {
               return '/replies/' + this.reply.id + '/favorites';
            }
        },

        methods: {
            toggle() {
                this.active ? this.destroy() : this.create();
            },

            create() {
                axios.post(this.endpoint);

                this.active = true;
                this.count++;
            },

            destroy() {
                axios.delete(this.endpoint);

                this.active = false;
                this.count--;
            }
        }
    }
</script>

<style scoped>

</style>