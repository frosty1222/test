export const routes = [
    {
      path: "/dashboard",
      name: "Dashboard",
      icon: "bi bi-speedometer2",
      children: []
    },
    {
      path: "/product",
      name: "Product List",
      icon: "bi bi-diagram-2",
      children: []
    },
    {
      path: "/category",
      name: "Category List",
      icon: "bi bi-bookmark-check-fill",
      children: [
        // {
        //   name: "Consulting",
        //   path: "/services/consulting",
        //   icon: "bi bi-diagram-2",
        // },
        // {
        //   name: "Support",
        //   path: "/services/support",
        //   icon: "bi bi-diagram-2",
        // },
      ]
    }
  ];