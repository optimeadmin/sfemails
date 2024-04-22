import React, { useMemo } from 'react'
import { createBrowserRouter, RouterProvider } from 'react-router-dom'
import { Layout } from './pages/Layout'
import { LayoutsPage } from './pages/layout/LayoutsPage'
import { CreateLayoutPage } from './pages/layout/CreateLayoutPage'

export function Router ({ basename }: { basename: string }) {
  const router = useMemo(() => createBrowserRouter([
    {
      element: <Layout/>, children: [
        { path: '/', element: <h3>Soy la ruta</h3> },
        { path: '/layouts', element: <LayoutsPage/> },
        { path: '/layouts/create', element: <CreateLayoutPage/> },
        { path: '/templates', element: <h3>Soy la ruta (templates)</h3> },
        { path: '/logs', element: <h3>Soy la ruta (logs)</h3> },
      ]
    },
    { path: '/*', element: <h1>Not found</h1> },
  ], { basename }), [basename])

  return (
    <RouterProvider router={router}/>
  )
}