import React, { createContext, PropsWithChildren, useContext } from 'react'

type UrlContextType = {
  basename: string,
  apiUrl: string,
}

const UrlContext = createContext<UrlContextType | null>(null)

type UrlProviderProps = {
  basename: string,
  apiUrl: string,
} & PropsWithChildren

export function UrlProvider ({ children, basename, apiUrl }: UrlProviderProps) {
  return (
    <UrlContext.Provider value={{ basename, apiUrl }}>
      {children}
    </UrlContext.Provider>
  )
}

export function useUrl (): UrlContextType {
  const context = useContext(UrlContext)

  if (!context) {
    throw new Error('useUrl can be used into <UrlProvider />')
  }

  return context
}