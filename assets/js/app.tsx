import 'vite/modulepreload-polyfill'
import 'react-toastify/dist/ReactToastify.css'
import React from 'react'
import { createRoot } from 'react-dom/client'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { Router } from './router'
import { LocaleProvider } from './contexts/LocaleContext'
import { axiosApi } from './api/axiosInstances'
import { UrlProvider } from './contexts/UrlContext'
import { ToastContainer } from 'react-toastify'
import { AppsProvider } from './contexts/AppsContext.tsx'

const $container = document.getElementById('emails_config_root') as HTMLElement
const basename = $container.dataset.basename ?? '/'
const endpointApi = $container.dataset.apiUrl ?? '/'
const locale = $container.dataset.locale ?? 'en'
const locales = JSON.parse($container.dataset.locales ?? '[]')
const appsCount = JSON.parse($container.dataset.appsCount ?? '0')

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
      <UrlProvider basename={basename} apiUrl={endpointApi}>
        <LocaleProvider locale={locale} locales={locales}>
          <AppsProvider appsCount={appsCount}>
            <Router basename={basename}/>
          </AppsProvider>
          <ToastContainer
            autoClose={3000}
            hideProgressBar
            position="top-center"
          />
        </LocaleProvider>
      </UrlProvider>
    </QueryClientProvider>
  </React.StrictMode>
)
