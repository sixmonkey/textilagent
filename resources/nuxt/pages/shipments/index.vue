<template>

  <v-row
    ref="holder"
    v-resize="onResize"
    class="fill-height primary overflow-hidden"
  >
    <v-col>
      <v-data-table
        ref="table"
        :footer-props="{
          'items-per-page-options': itemsPerPageOptions,
          'show-current-page': true,
          'show-first-last-page': true
        }"
        :headers="headers"
        :items="shipments"
        :loading="$fetchState.pending"
        :options.sync="options"
        :server-items-length="meta?.total ?? 0"
        class="elevation-1"
        :height="tableHeight"
        fixed-header
      >
        <template #top>
          <v-container fluid>
            <v-row class="table--header">
              <v-col md="3">
                <app-form-autocomplete
                  v-model="filter.seller_id"
                  avatars
                  entity="company"
                  item-text="name"
                  item-value="id"
                  label="Seller"
                  name="seller"
                  return-id
                  clearable
                />
              </v-col>
              <v-col md="3">
                <app-form-autocomplete
                  v-model="filter.purchaser_id"
                  avatars
                  entity="company"
                  item-text="name"
                  item-value="id"
                  label="Purchaser"
                  name="purchaser"
                  return-id
                  clearable
                />
              </v-col>
              <v-col md="3">
                <app-form-datepicker
                  v-model="filter.date_between"
                  label="Month"
                />
              </v-col>
              <v-col md="3">
                <app-form-autocomplete
                  v-model="filter.order_id"
                  entity="order"
                  item-text="contract"
                  item-value="id"
                  label="Order"
                  name="order"
                  return-id
                  clearable
                />
              </v-col>
            </v-row>
          </v-container>
        </template>

        <template #[`item.date`]="{ item }">
          {{ new Date(item.date).toLocaleString(undefined, {year: 'numeric', month: '2-digit', day: '2-digit'}) }}
        </template>
        <template #[`item.seller`]="{ item }">
          <app-avatar :name="item.seller.name" size="24" tooltip/>
          <span class="ml-2">{{ item.seller.name }}</span>
        </template>
        <template #[`item.purchaser`]="{ item }">
          <app-avatar :name="item.purchaser.name" size="24" tooltip/>
          <span class="ml-2">{{ item.purchaser.name }}</span>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script>
import _ from 'lodash'

export default {
  name: 'ShipmentsIndex',
  meta: {
    title: 'Shipments',
    icon: 'truck-delivery',
  },
  data() {
    return {
      tableHeight: 'auto',
      itemsPerPageOptions: [10, ..._.range(25, this.$config.page.max_size, 25), parseInt(this.$config.page.max_size)],
      shipments: [],
      meta: {
        current_page: 1,
        per_page: this.$config.page.default_size,
        total: 0
      },
      headers: [
        {
          text: 'Date',
          align: 'start',
          value: 'date',
        },
        {
          text: 'Invoice #',
          value: 'invoice',
        },
        {
          text: 'Seller',
          value: 'seller',
          sortable: false,
        },
        {
          text: 'Purchaser',
          value: 'purchaser',
          sortable: false,
        },
        {
          text: 'Items',
          value: 'shipment_items_count',
          align: 'right',
          sortable: false,
        },
        {
          text: 'Actions',
          value: 'actions',
          sortable: false,
          align: 'right',
        },
      ],
      filter: this.$route.query?.filter ?? {
        seller_id: null,
        purchaser_id: null,
        date_between: [],
      },
      options: this.$queryToDatatable()
    }
  },
  async fetch() {
    const result = await this.$jsonApi
      .get('/shipments', {
        include: 'seller,purchaser,shipment_items_count',
        page: {size: this.$config.page.default_size},
        ...this.$route.query
      })
      .then(result => {
        return result
      })

    this.shipments = [...result.data]
    this.meta = result.meta
    this.options = this.$queryToDatatable();

    this.$refs.table.$el.querySelector('.v-data-table__wrapper').scroll(0, 0)
  },
  watch: {
    '$route.query': '$fetch',
    filter: {
      handler() {
        this.options.page = 1
      },
      deep: true
    }
  },
  mounted() {
    this.$watch(vm => [vm.filter, vm.options], val => {
      this.$router.push({
        query: this.$datatableToJsonApi({...this.options, ...{filter: this.filter}}),
      })
    }, {
      immediate: true,
      deep: true
    })
  },
  methods: {
    onResize() {
      this.tableHeight = document.getElementById('sidebar').offsetHeight
        - this.$refs.table.$el.querySelector('.table--header').offsetHeight
        - this.$refs.table.$el.querySelector('.v-data-footer').offsetHeight
        - 1
    }
  },
  fetchOnServer: false,
}
</script>
