import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { Layout } from '../types'
import { getLayout, saveLayout } from '../api/layouts'
import { addServerError } from '../utils/errors'
import { AxiosError } from 'axios'
import { FieldValues, UseFormSetError } from 'react-hook-form'
import { getConfigs } from '../api/configs.ts'

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

export function useGetConfigs () {
  const { isLoading, data: configs } = useQuery({
    queryKey: ['configs'],
    queryFn: ({ signal }) => getConfigs(signal),
  })

  return {
    isLoading,
    configs,
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