<template>
    
</template>

<script>
    export default {
        name: "reply",
        props: ['attributes'],

        data() {
            return {
                editing: false,
                body: this.attributes.body
            };
        },

        methods: {
            update() {
                let url = '/replies/' + this.attributes.id;
                axios.patch(url, {
                    body:this.body
                });

                this.editing = false;

                flash('已更新');
            }
        }
    }
</script>

<style scoped>

</style>