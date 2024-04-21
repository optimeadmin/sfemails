import React from 'react'
import { createRoot } from 'react-dom/client'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'

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
      <h1>Hi</h1>
    </QueryClientProvider>
  </React.StrictMode>
)
