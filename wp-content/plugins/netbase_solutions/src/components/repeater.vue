<template>
    <v-data-table
        :headers="saleTableHeaders"
        :items="items.recent_income"
        class="sale-stats-table section-wrap"
        :rows-per-page-items="[7]"
    >
        <template slot="items" scope="props">
            <td>{{props.item.label}}</td>
            <td>{{props.item.order_count}}</td>
            <td>{{currencySymbol}}{{props.item.total_sale}}</td>                    
        </template>
    </v-data-table>
</template>

<script>

</script>