import React, { useMemo } from 'react'
import { createBrowserRouter, RouterProvider } from 'react-router-dom'
import { Layout } from './pages/Layout'
import { LayoutsPage } from './pages/layout/LayoutsPage'
import { CreateLayoutPage } from './pages/layout/CreateLayoutPage'
import { EditLayoutPage } from './pages/layout/EditLayoutPage'
import { ConfigsPage } from './pages/config/ConfigsPage.tsx'
import { CreateConfigPage } from './pages/config/CreateConfigPage.tsx'
import { EditConfigPage } from './pages/config/EditConfigPage.tsx'
import { TemplatesPage } from './pages/templates/TemplatesPage.tsx'
import { CreateTemplatePage } from './pages/templates/CreateTemplatePage.tsx'
import { EditTemplatePage } from './pages/templates/EditTemplatePage.tsx'
import { LogsPage } from './pages/logs/LogsPage.tsx'

export function Router ({ basename }: { basename: string }) {
  const router = useMemo(() => createBrowserRouter([
    {
      element: <Layout/>, children: [
        {
          path: '/', children: [
            { index: true, element: <ConfigsPage/> },
            { path: '/config/create', element: <CreateConfigPage/> },
            { path: '/config/edit/:uuid', element: <EditConfigPage/> },
          ]
        },
        {
          path: '/layouts', children: [
            { index: true, element: <LayoutsPage/> },
            { path: 'create', element: <CreateLayoutPage/> },
            { path: 'edit/:uuid', element: <EditLayoutPage/> },
          ]
        },
        {
          path: '/templates', children: [
            { index: true, element: <TemplatesPage/> },
            { path: 'create', element: <CreateTemplatePage/> },
            { path: 'edit/:uuid', element: <EditTemplatePage/> },
          ]
        },
        { path: '/logs', element: <LogsPage/> },
      ]
    },
    { path: '/*', element: <h1>Not found</h1> },
  ], { basename }), [basename])

  return (
    <RouterProvider router={router}/>
  )
}