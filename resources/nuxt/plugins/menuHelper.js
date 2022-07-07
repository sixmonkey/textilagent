import _ from 'underscore'

export default ({ app }, inject) => {
  const menuOutput = {}
  _.each(app.$config.menus, function (menus, key) {
    const menuComputed = []
    _.each(menus, function (menu) {
      const subMenu = []
      _.each(menu, function (item) {
        const route = _.findWhere(app.router.options.routes, { name: item })

        if (route === undefined) return

        subMenu.push({
          title: route.meta.title ?? 'Unknown',
          icon: `mdi-${route.meta.icon}` ?? 'mdi-help-circle',
          to: route.path,
        })
      })
      menuComputed.push(subMenu)
    })
    menuOutput[key] = menuComputed
  })
  inject('menus', menuOutput)
}
