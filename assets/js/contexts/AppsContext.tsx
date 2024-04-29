import React, { createContext, PropsWithChildren, useContext } from 'react'

type AppsContextType = {
  appsCount: number,
}

const AppsContext = createContext<AppsContextType | null>(null)

type AppsProviderProps = {
  appsCount: number,
} & PropsWithChildren

export function AppsProvider ({ children, appsCount }: AppsProviderProps) {
  return (
    <AppsContext.Provider value={{ appsCount }}>
      {children}
    </AppsContext.Provider>
  )
}

export function useApps (): AppsContextType {
  const context = useContext(AppsContext)

  if (!context) {
    throw new Error('useApps can be used into <AppsProvider />')
  }

  return context
}