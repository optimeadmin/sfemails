import React, { useMemo } from 'react'
import { createBrowserRouter, RouterProvider } from 'react-router-dom'
import { Layout } from './pages/Layout'
import { LayoutsPage } from './pages/layout/LayoutsPage'
import { CreateLayoutPage } from './pages/layout/CreateLayoutPage'
import { EditLayoutPage } from './pages/layout/EditLayoutPage'
import { ConfigsPage } from './pages/config/ConfigsPage.tsx'
import { CreateConfigPage } from './pages/config/CreateConfigPage.tsx'

export function Router ({ basename }: { basename: string }) {
  const router = useMemo(() => createBrowserRouter([
    {
      element: <Layout/>, children: [
        {
          path: '/', children: [
            { index: true, element: <ConfigsPage/> },
            { path: '/config/create', element: <CreateConfigPage/> },
            { path: '/config/edit/:uuid', element: <EditLayoutPage/> },
          ]
        },
        {
          path: '/layouts', children: [
            { index: true, element: <LayoutsPage/> },
            { path: 'create', element: <CreateLayoutPage/> },
            { path: 'edit/:uuid', element: <EditLayoutPage/> },
          ]
        },
        { path: '/templates', element: <h3>Soy la ruta (templates) Siiii</h3> },
        { path: '/logs', element: <h3>Soy la ruta (logs)</h3> },
      ]
    },
    { path: '/*', element: <h1>Not found</h1> },
  ], { basename }), [basename])

  return (
    <RouterProvider router={router}/>
  )
}