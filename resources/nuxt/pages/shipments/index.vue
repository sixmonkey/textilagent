<template>
  <v-data-table
    ref="table"
    :footer-props="{ 'items-per-page-options': [10, 25, 50, 100] }"
    :headers="headers"
    :height="maxHeight"
    :items="shipments"
    :loading="$fetchState.pending"
    :options.sync="options"
    :server-items-length="meta.total"
    class="elevation-1"
    fixed-header
  >
    <template #top>
      <v-container fluid>
        <v-row class="table--header">
          <v-col md="4">
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
          <v-col md="4">
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
</template>

<script>
import _ from 'lodash'

export default {
  name: 'ShipmentsIndex',
  meta: {
    title: 'Shipments',
    icon: 'truck-delivery',
  },
  watchQuery: true, // TODO: nothing working to watch the query
  data() {
    return {
      shipments: [],
      meta: {},
      maxHeight: 'auto',
      options: {
        page: 1,
        itemsPerPage: 25,
      },
      filter: this.$route.query.filter ?? {},
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
    }
  },
  async fetch() {
    const sorts = this.options?.sortBy?.map((sort, index) => {

      return this.options?.sortDesc[index] ? `-${sort}` : sort
    }) ?? []

    const params = {
      'page[size]': this.options.itemsPerPage,
      'page[number]': this.options.page,
      include: 'seller,purchaser,shipment_items_count',
      'filter[seller_id]': this.filter.seller_id,
      'filter[purchaser_id]': this.filter.purchaser_id,
    }

    if (sorts.length) {
      params.sort = sorts.join()
    }

    const result = await this.$axios.get('/shipments', {
      params,
    })
    this.shipments = [...result.data.data]
    this.meta = result.data.meta
    this.$nextTick(() => this.onResize())
  },
  watch: {
    options: {
      handler() {
        this.$fetch()
      },
      deep: true,
    },
    filter: {
      handler() {
        this.$router.push({
          path: this.$route.fullPath || this.$route.path,
          query: {
            filter: _.omitBy(this.filter, _.isNull) ?? null,
          },
        })
        this.$fetch()
      },
      deep: true,
    },
    '$route.query': '$fetch' // TODO: nothing working to watch the query
  },
  methods: {
    onResize() {
      this.maxHeight = 'auto'
      // TODO: this is ugly
      if (this.$refs.holder && !this.$vuetify.breakpoint.smAndDown) {
        this.maxHeight = Math.floor(
          Math.min(
            this.$refs.holder.clientHeight -
            this.$refs.table.$el.querySelector('.v-data-footer')
              .clientHeight -
            this.$refs.table.$el.querySelector('.table--header')
              .clientHeight -
            1,
            this.$refs.table.$el.querySelector('.v-data-table__wrapper>table')
              .clientHeight
          )
        )
      }
    },
  },
}
</script>
