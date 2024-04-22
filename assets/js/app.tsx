import React from 'react'
import { createRoot } from 'react-dom/client'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { Router } from './Router'
import { LocaleProvider } from './contexts/LocaleContext'
import { axiosApi } from './api/axiosInstances'

const $container = document.getElementById('emails_config_root') as HTMLElement
const basename = $container.dataset.basename ?? '/'
export const endpointApi = $container.dataset.apiUrl ?? '/'
const root = createRoot($container)

if (endpointApi.length > 1) {
  axiosApi.defaults.baseURL = endpointApi
}

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
      <LocaleProvider locale={'en'} locales={['en', 'es']}>
        <Router basename={basename}/>
      </LocaleProvider>
    </QueryClientProvider>
  </React.StrictMode>
)
