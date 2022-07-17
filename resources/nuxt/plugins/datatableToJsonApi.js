import cleanDeep from 'clean-deep'


export default ({app}, inject) => {
  inject('datatableToJsonApi', function (options) {
    const sort = (options?.sortBy?.map((sort, index) => {
      return options?.sortDesc[index] ? `-${sort}` : sort
    }) ?? []).join()

    options.itemsPerPage = parseInt(options.itemsPerPage);
    options.page = parseInt(options.page);

    return cleanDeep({
      sort,
      page: {
        size: options.itemsPerPage !== parseInt(app.$config.page.default_size) ? options.itemsPerPage : null,
        number: options.page > 1 ? options.page : null
      },
      filter: options.filter,
      include: options.include
    })
  })
  inject('queryToDatatable', function () {
    const sorts = app.context?.route?.query?.sort ? app.context?.route?.query?.sort.split() : []
    const sortDesc = []
    const sortBy = sorts.map((sort) => {
      sortDesc.push(sort.startsWith('-'))
      return sort.replace(/^-/, '')
    })
    return {
      sortBy,
      sortDesc,
      itemsPerPage: app.context?.route?.query?.page?.size ?? parseInt(app.$config.page.default_size),
      page: app.context?.route?.query?.page?.number ?? 1
    }
  })
}
