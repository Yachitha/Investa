<template>
    <v-app>
        <v-container grid-list-md fluid>
            <v-layout column justify-start fill-height>
                <v-flex xs12>
                    <v-card light color="dark">

                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    import JsPDF from 'jspdf';
    export default {
        beforeMount() {
            this.getData();
        },
        name: 'PDFGenerator',
        data () {
            return {
                test: "Southern Property Developers"
            }
        },
        methods: {
            createPdf() {
                console.log("method reached!");
                var doc = new JsPDF();
                doc.text(20, 20, this.test);
                doc.save('test.pdf');
            },
            getData() {

            }
        }
    }
</script>

<style scoped>

</style>
