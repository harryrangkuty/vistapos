<script>
import "leaflet/dist/leaflet.css";
import { LMap, LTileLayer, LPolygon, LPopup } from "@vue-leaflet/vue-leaflet";

export default {
    props: ["constant"],

    components: {
        LMap,
        LTileLayer,
        LPolygon,
        LPopup,
    },
    data() {
        return {
            zoom: 16,
        };
    },

    mounted() {
        console.log(this.constant.maps);
    },
};
</script>

<template>
    <a-row type="flex" justify="center">
        <a-col :span="12">
            <div style="height: 725px; width: 100%">
                <l-map
                    ref="map"
                    v-model:zoom="zoom"
                    :center="[3.5627, 98.6568]"
                    :use-global-leaflet="false"
                    :max-bounds="[
                        [3.5536, 98.64939],
                        [3.57193, 98.66394],
                    ]"
                >
                    <l-tile-layer
                        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                        layer-type="base"
                        name="OpenStreetMap"
                        attribution="&copy; <a href='http://osm.org/copyright'>OpenStreetMap</a> contributors"
                    />
                    <template v-for="obj in constant.maps">
                        <l-polygon
                            :lat-lngs="obj.poin"
                            :color="obj.warna"
                            :fill="true"
                            :fillOpacity="0.5"
                            :fillColor="obj.warna"
                        >
                            <l-popup :content="obj.nama" />
                        </l-polygon>
                    </template>
                </l-map>
            </div>
        </a-col>
        <a-col :span="12">
            <pre>{{ constant.maps }}</pre>
        </a-col>
    </a-row>
</template>

<style></style>
