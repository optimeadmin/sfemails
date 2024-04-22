import React from 'react'
import { FormErrors, FormLabel, FormRow } from '../ui/form/field'
import { Button, Col, FormControl, Row } from 'react-bootstrap'
import { FieldWithLocale, TranslatableFields } from '../ui/form/TranslatableFields'
import { ActionsContainer } from '../ui/form/ActionsContainer'
import { Link } from 'react-router-dom'
import { FormProvider, useForm } from 'react-hook-form'
import { Layout } from '../../types'
import { useMutation } from '@tanstack/react-query'

export function LayoutForm () {
  const form = useForm<Layout>()
  const { register } = form

  const { mutateAsync, isPending } = useMutation({
    async mutationFn () {
      console.log('Mutando')
    }
  })

  function sendForm (data: Layout) {
    console.log({ data })
  }

  return (
    <form onSubmit={form.handleSubmit(sendForm)}>
      <FormProvider {...form}>
        <FormRow className="mb-3" name="description">
          <FormLabel>Description</FormLabel>
          <FormControl as="textarea" {...register('description', { required: true })}/>
          <FormErrors/>
        </FormRow>

        <FormRow>
          <FormLabel>Content</FormLabel>
          <Row>
            <TranslatableFields
              render={({ locale }) => (
                <Col xs={12} xl={6}>
                  <FormRow name={`content.${locale}`}>
                    <FieldWithLocale locale={locale} className="mb-3">
                      <FormControl
                        as="textarea" {...register(`content.${locale}`, { required: true })}
                        rows={15}
                      />
                    </FieldWithLocale>
                    <FormErrors/>
                  </FormRow>
                </Col>
              )}
            />
          </Row>
        </FormRow>
      </FormProvider>

      <ActionsContainer>
        <Link to="/layouts" className="btn btn-outline-secondary">Cancel</Link>
        <Button type="submit">Create</Button>
      </ActionsContainer>
    </form>
  )
}
