import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { Layout } from '../types'
import { getLayout, getLayouts, saveLayout } from '../api/layouts'
import { addServerError } from '../utils/errors'
import { AxiosError } from 'axios'
import { FieldValues, UseFormSetError } from 'react-hook-form'

export function useSaveLayout (addError?: UseFormSetError<FieldValues>) {
  const queryClient = useQueryClient()
  const { mutateAsync, isPending } = useMutation({
    async mutationFn (layout: Layout) {
      await saveLayout(layout)
    },
    async onSuccess () {
      return queryClient.invalidateQueries({ queryKey: ['layouts'] })
    },
    onError (error: AxiosError) {
      addServerError(error, addError)
    }
  })

  return {
    save: mutateAsync,
    isPending,
  }
}

export function useGetLayouts () {
  const { isLoading, data: layouts } = useQuery({
    queryKey: ['layouts'],
    queryFn: ({ signal }) => getLayouts(signal),
  })

  return {
    isLoading,
    layouts,
  }
}

export function useGetLayoutByUuid (uuid: string) {
  const { isLoading, data: layout } = useQuery({
    queryKey: ['layouts', 'item', uuid],
    queryFn: ({ signal }) => getLayout(uuid, signal),
  })

  return {
    isLoading,
    layout,
  }
}