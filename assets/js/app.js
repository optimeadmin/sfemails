import React from 'react'
import { createRoot } from 'react-dom/client'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { Router } from './Router'

const $container = document.getElementById('emails_config_root')
const basename = $container.dataset.basename ?? '/'
export const endpointApi = $container.dataset.endpointApi ?? '/'
const root = createRoot($container)

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      refetchOnWindowFocus: false
    }
  }
})

root.render(
  <React.StrictMode>
    <QueryClientProvider client={queryClient}>
      <Router basename={basename}/>
    </QueryClientProvider>
  </React.StrictMode>
)
